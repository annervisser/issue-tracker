import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BoardComponent } from './board/board.component';
import { IssueCardComponent } from './issue-card/issue-card.component';
import { DataAccessModule } from '@issue-tracker/data-access';
import { ReactiveFormsModule } from '@angular/forms';

@NgModule({
    imports: [CommonModule, DataAccessModule, ReactiveFormsModule],
    declarations: [BoardComponent, IssueCardComponent]
})
export class KanbanBoardModule {
}
