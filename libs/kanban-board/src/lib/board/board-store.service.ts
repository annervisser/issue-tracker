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
            let storyStateId: string;
            let storyIndex: number;

            for (const [stateId, stories] of Object.entries(this.state.storiesByState)) {
                storyIndex = stories.findIndex(s => s.id === storyId);
                if (storyIndex > -1) {
                    storyStateId = stateId;
                    break;
                }
            }

            this.updateState(draft => {
                draft.storiesByState[storyStateId].splice(storyIndex, 1);
            });
        });
    }

    moveStory(storyId: string, stateId: string) {
        const state = this.state;
        const [previousStateId] = Object.entries(state.storiesByState)
            .find(([_, stories]) => stories.some(story => story.id === storyId)) ?? [];

        if (!previousStateId) {
            throw new Error('Story doesnt exist in any state');
        }

        const indexInPreviousState = state.storiesByState[previousStateId].findIndex(story => story.id === storyId);
        const story = state.storiesByState[previousStateId][indexInPreviousState];

        this.updateState(draft => {
            draft.storiesByState[previousStateId].splice(indexInPreviousState, 1);
            draft.storiesByState[stateId].push(story);
        });
    }

    reorderStory(storyId: string, stateId: string, moveToIndex: number) {
        const state = this.state;
        const currentIndex = state.storiesByState[stateId].findIndex(story => story.id === storyId);
        const story = state.storiesByState[stateId][currentIndex];
        this.updateState(draft => {
            draft.storiesByState[stateId].splice(currentIndex, 1);
            draft.storiesByState[stateId].splice(moveToIndex, 0, story);
        });
    }
}
