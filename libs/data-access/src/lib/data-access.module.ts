import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IssueClient } from './services/issue-client';
import { HttpClientModule } from '@angular/common/http';

@NgModule({
    imports: [CommonModule, HttpClientModule],
    providers: [IssueClient]
})
export class DataAccessModule {
}
