import { Injectable } from '@angular/core';
import { combineLatest, map, Observable } from 'rxjs';
import { State, StateClient, Story, StoryClient } from '@issue-tracker/data-access';
import { ComponentState } from '@issue-tracker/component-state';
import { Immutable } from 'immer';

export interface BoardState {
    states: Array<State>;
    storiesByState: Record<string, Array<Story>>;
}

@Injectable()
export class BoardStore extends ComponentState<BoardState> {

    constructor(
        private readonly storyClient: StoryClient,
        private readonly stateClient: StateClient
    ) {
        super({
            states: [],
            storiesByState: {}
        });

        this.states$.subscribe(this.fetchStories.bind(this));
    }

    readonly states$: Observable<ReadonlyArray<State>> = this.select$((state) => state.states);
    readonly storiesByState$: Observable<Immutable<Record<string, Array<Story>>>> = this.select$((state) => state.storiesByState);

    public fetchStates(): void {
        this.stateClient.listStates()
            .then((states) => this.patchState({ states }));
    }

    public fetchStories(states: ReadonlyArray<State>): void {
        const observables: Array<Promise<[string, Array<Story>]>> = states.map(
            (state) => this.storyClient.listStoriesInState(state.id).then(stories => [state.id, stories])
        );

        combineLatest(observables)
            .pipe(map(r => Object.fromEntries(r)))
            .subscribe((storiesByState) => this.patchState({ storiesByState }));

    }

    public createStory(data: { title: string, stateId: string }) {
        this.storyClient.createStory(data).then(async ({ storyId }) => {
            const story = await this.storyClient.getStory(storyId);
            this.updateState(s => {
                s.storiesByState[data.stateId].push(story);
            });
        });
    }

    public deleteStory(storyId: string) {
        this.storyClient.deleteStory(storyId).then(() => {
            const { stateId, storyIndex } = this.findStory(storyId);

            this.updateState(draft => {
                draft.storiesByState[stateId].splice(storyIndex, 1);
            });
        });
    }

    async moveStory(storyId: string, stateId: string, moveToIndex: number): Promise<void> {
        const { stateId: currentStateId } = this.findStory(storyId);

        let movePromise: Promise<void> = Promise.resolve();
        if (stateId !== currentStateId) {
            movePromise = this.changeStoryStateTo(storyId, stateId);
        }

        const {
            confirm: confirmReorder,
            revert: revertReorder
        } = this.reorderStoryLocally(storyId, stateId, moveToIndex);
        const afterStory = this.state.storiesByState[stateId][moveToIndex - 1]?.id || null;

        try {
            await movePromise;
            await this.storyClient.reorderStory(storyId, afterStory);
            confirmReorder();
        } catch (e) {
            revertReorder();
            throw e;
        }
    }

    private async changeStoryStateTo(storyId: string, stateId: string) {
        const { confirm, revert } = this.moveStoryLocally(storyId, stateId);
        try {
            await this.storyClient.changeState(storyId, stateId);
            confirm();
            return;
        } catch (e) {
            revert();
            throw e;
        }
    }

    //
    // async reorderStory(storyId: string, stateId: string, moveToIndex: number): Promise<void> {
    //     const { confirm, revert } = this.reorderStoryLocally(storyId, stateId, moveToIndex);
    //     const afterStory = this.state.storiesByState[stateId][moveToIndex - 1]?.id;
    //
    //     try {
    //         await this.storyClient.reorderStory(storyId, afterStory);
    //         confirm();
    //         return;
    //     } catch (e) {
    //         revert();
    //         throw e;
    //     }
    // }

    private moveStoryLocally(storyId: string, stateId: string) {
        const { stateId: previousStateId, storyIndex: indexInPreviousState, story } = this.findStory(storyId);

        const { confirm, revert } = this.optimisticUpdate(draft => {
            draft.storiesByState[previousStateId].splice(indexInPreviousState, 1);
            draft.storiesByState[stateId].push(story);
        });
        return { confirm, revert };
    }

    private reorderStoryLocally(storyId: string, stateId: string, moveToIndex: number) {
        const { story, storyIndex: currentIndex } = this.findStory(storyId);

        return this.optimisticUpdate(draft => {
            draft.storiesByState[stateId].splice(currentIndex, 1);
            draft.storiesByState[stateId].splice(moveToIndex, 0, story);
        });
    }

    private findStory(storyId: string): { storyIndex: number; stateId: string; story: Story } {
        const storiesByState = this.state.storiesByState;

        for (const [stateId, stories] of Object.entries(storiesByState)) {
            const storyIndex = stories.findIndex(s => s.id === storyId);
            const story = stories[storyIndex];
            if (storyIndex > -1) {
                return { story, stateId, storyIndex };
            }
        }

        throw new Error('Story not found');
    }
}
