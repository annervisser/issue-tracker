import { Component, OnInit } from '@angular/core';
import { BoardService } from './board.service';

@Component({
    selector: 'issue-tracker-board',
    templateUrl: './board.component.html',
    styleUrls: ['./board.component.scss'],
    providers: [BoardService]
})
export class BoardComponent implements OnInit {
    constructor(public readonly boardService: BoardService) {
    }

    ngOnInit(): void {
    }
}
