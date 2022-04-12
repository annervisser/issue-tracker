import { Injectable } from '@angular/core';
import { StoryClient } from '../../../../data-access/src/lib/services/story-client.service';
import { BehaviorSubject, combineLatest, lastValueFrom, Observable, shareReplay, switchMap } from 'rxjs';
import { Story } from '../../../../data-access/src/lib/types/story';
import { StateClient } from '../../../../data-access/src/lib/services/state-client.service';
import { State } from '../../../../data-access/src/lib/types/state';

// TODO Change this to a ngrx (component?)store
@Injectable()
export class BoardService {
    private readonly reloadSubject = new BehaviorSubject<void>(undefined);

    states$: Observable<Array<State>> = this.reloadSubject
        .pipe(
            switchMap(() => this.stateClient.listStates()),
            shareReplay(1)
        );

    stories$: Observable<Record<string, Array<Story>>> = this.states$.pipe(
        switchMap((states) => {
            const map: Record<string, Observable<Array<Story>>> = {};
            for (const state of states) {
                map[state.id] = this.storyClient.listStoriesInState(state.id);
            }
            return combineLatest(map);
        }),
        shareReplay(1)
    );

    constructor(
        private readonly storyClient: StoryClient,
        private readonly stateClient: StateClient
    ) {
    }

    public createStory(title: string, stateId: string): Promise<void> {
        const createStory$ = this.storyClient.createStory({ title, stateId });
        return lastValueFrom(createStory$).then(() => {
            this.reloadSubject.next();
        });
    }
}
