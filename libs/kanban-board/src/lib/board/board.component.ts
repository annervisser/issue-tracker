import { ChangeDetectionStrategy, Component, OnInit, TrackByFunction } from '@angular/core';
import { BoardStore } from './board-store.service';
import { CDK_DROP_LIST_GROUP, CdkDragDrop, CdkDropListGroup } from '@angular/cdk/drag-drop';
import { State, Story } from '@issue-tracker/data-access';

@Component({
    selector: 'issue-tracker-board',
    templateUrl: './board.component.html',
    styleUrls: ['./board.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    providers: [
        BoardStore,
        { provide: CDK_DROP_LIST_GROUP, useValue: new CdkDropListGroup() } // Connect all drop lists together
    ]
})
export class BoardComponent implements OnInit {
    constructor(public readonly boardStore: BoardStore) {
        this.boardStore.fetchStates();
    }

    ngOnInit(): void {
    }

    createStory(title: string, stateId: string): void {
        this.boardStore.createStory({ title, stateId });
    }

    deleteStory(storyId: string): void {
        this.boardStore.deleteStory(storyId);
    }

    trackByStoryId: TrackByFunction<Story> = (index, item) => item.id;
    trackByStateId: TrackByFunction<State> = (index, item) => item.id;

    drop(event: CdkDragDrop<string, string, string>) {
        const stateId = event.container.data;
        const previousStateId = event.previousContainer.data;
        const storyId = event.item.data;

        if (stateId !== previousStateId) {
            this.boardStore.moveStory(storyId, stateId);
        }

        this.boardStore.reorderStory(storyId, stateId, event.currentIndex);
    }
}
