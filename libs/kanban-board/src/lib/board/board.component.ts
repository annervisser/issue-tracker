import { ChangeDetectionStrategy, Component, OnInit } from '@angular/core';
import { BoardStore } from './board.store';

@Component({
    selector: 'issue-tracker-board',
    templateUrl: './board.component.html',
    styleUrls: ['./board.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    providers: [BoardStore]
})
export class BoardComponent implements OnInit {
    constructor(public readonly boardStore: BoardStore) {
        this.boardStore.fetchStates();
    }

    ngOnInit(): void {
    }

    createStory(title: string, stateId: string) {
        this.boardStore.createStory({ title, stateId });
    }
}
