import { Component, Input, OnInit } from '@angular/core';

@Component({
    selector: 'issue-tracker-story-card',
    templateUrl: './story-card.component.html',
    styleUrls: ['./story-card.component.scss']
})
export class StoryCardComponent implements OnInit {
    // TODO replace this input with an Issue object
    @Input() title!: string;

    constructor() {
    }

    ngOnInit(): void {
    }
}
