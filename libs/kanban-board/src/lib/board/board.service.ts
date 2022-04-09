import { Injectable } from '@angular/core';
import { IssueClient } from '../../../../data-access/src/lib/services/issue-client';
import { lastValueFrom, Observable, shareReplay, startWith, Subject, switchMap } from 'rxjs';
import { Issue } from '../../../../data-access/src/lib/types/issue';

// TODO Change this to a ngrx (component?)store
@Injectable()
export class BoardService {
    private readonly reloadStoriesSubject = new Subject<void>();
    issues$: Observable<Array<Issue>> = this.reloadStoriesSubject.pipe(
        startWith(void 0),
        switchMap(() => this.issueClient.listStories()),
        shareReplay(1)
    );

    constructor(private readonly issueClient: IssueClient) {
    }

    public createStory(title: string): Promise<void> {
        const createStory$ = this.issueClient.createStory({ title });
        return lastValueFrom(createStory$).then(() => {
            this.reloadStoriesSubject.next(void 0);
        });
    }
}
