import { Injectable } from '@angular/core';
import { ComponentStore, tapResponse } from '@ngrx/component-store';
import { combineLatest, Observable, switchMap } from 'rxjs';
import { State, StateClient, Story, StoryClient } from '@issue-tracker/data-access';

export interface BoardState {
    states: Array<State>;
    storiesByState: Record<string, Array<Story>>;
}

@Injectable()
export class BoardStore extends ComponentStore<BoardState> {

    constructor(
        private readonly storyClient: StoryClient,
        private readonly stateClient: StateClient
    ) {
        super({
            states: [],
            storiesByState: {}
        });

        this.fetchStories(this.states$);
    }

    readonly states$: Observable<Array<State>> = this.select((state) => state.states);
    readonly storiesByState$: Observable<Record<string, Array<Story>>> = this.select((state) => state.storiesByState);

    readonly fetchStates = this.effect((origin$: Observable<void>) => {
        return origin$.pipe(
            switchMap(() => this.stateClient.listStates().pipe(
                tapResponse(
                    (states) => this.patchState({ states }),
                    console.error
                )
            ))
        );
    });

    readonly fetchStories = this.effect((origin$: Observable<Array<State>>) => {
        return origin$.pipe(
            switchMap((states) => {
                const map: Record<string, Observable<Array<Story>>> = {};
                for (const state of states) {
                    map[state.id] = this.storyClient.listStoriesInState(state.id);
                }
                return combineLatest(map).pipe(
                    tapResponse(
                        (storiesByState) => this.patchState({ storiesByState }),
                        console.error
                    )
                );
            })
        );
    });

    readonly createStory = this.effect<{ title: string, stateId: string }>((data$: Observable<{ title: string, stateId: string }>) => {
        return data$.pipe(
            switchMap(({ title, stateId }) => this.storyClient.createStory({ title, stateId }).pipe(
                switchMap(({ storyId }) => this.storyClient.getStory(storyId)),
                tapResponse(
                    (story) => this.patchState(state => ({
                        storiesByState: {
                            ...state.storiesByState,
                            [stateId]: [...state.storiesByState[stateId], story]
                        }
                    })),
                    console.error
                )
            ))
        );
    });
}
