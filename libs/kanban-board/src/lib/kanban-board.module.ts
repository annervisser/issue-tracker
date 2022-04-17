import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BoardComponent } from './board/board.component';
import { StoryCardComponent } from './story-card/story-card.component';
import { DataAccessModule } from '@issue-tracker/data-access';
import { ReactiveFormsModule } from '@angular/forms';
import { DragDropModule } from '@angular/cdk/drag-drop';

@NgModule({
    imports: [
        CommonModule,
        DataAccessModule,
        ReactiveFormsModule,
        DragDropModule
    ],
    declarations: [BoardComponent, StoryCardComponent]
})
export class KanbanBoardModule {
}
