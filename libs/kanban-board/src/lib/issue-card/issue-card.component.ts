import { Component, Input, OnInit } from '@angular/core';

@Component({
    selector: 'issue-tracker-issue-card',
    templateUrl: './issue-card.component.html',
    styleUrls: ['./issue-card.component.scss']
})
export class IssueCardComponent implements OnInit {
    // TODO replace this input with an Issue object
    @Input() title!: string;

    constructor() {
    }

    ngOnInit(): void {
    }
}
