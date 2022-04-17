import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
    selector: 'issue-tracker-story-card',
    templateUrl: './story-card.component.html',
    styleUrls: ['./story-card.component.scss']
})
export class StoryCardComponent implements OnInit {
    // TODO replace this input with an Issue object
    @Input() title!: string;

    @Output() delete = new EventEmitter<void>();

    constructor() {
    }

    ngOnInit(): void {
    }
}
