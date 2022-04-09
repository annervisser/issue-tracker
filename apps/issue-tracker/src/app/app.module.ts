import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppComponent } from './app.component';
import { RouterModule } from '@angular/router';
import { BoardComponent } from '../../../../libs/kanban-board/src/lib/board/board.component';

@NgModule({
    declarations: [AppComponent],
    imports: [
        BrowserModule,
        RouterModule.forRoot(
            [
                {
                    path: '',
                    component: BoardComponent
                }
            ],
            { initialNavigation: 'enabledBlocking' }
        )
    ],
    providers: [],
    bootstrap: [AppComponent]
})
export class AppModule {}
