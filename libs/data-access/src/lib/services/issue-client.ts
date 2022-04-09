import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { Issue } from '../types/issue';
import { Temporal } from '@js-temporal/polyfill';

interface IssueResponse {
    id: string;
    title: string;
    createdAt: string;
}

const issueResponseToIssue = (response: IssueResponse): Issue => {
    return ({
        ...response,
        createdAt: Temporal.Instant.from(response.createdAt)
    });
};

interface CreateIssueRequest {
    title: string;
}

interface CreateStoryResponse {
    storyId: string;
}

@Injectable()
export class IssueClient {
    private readonly baseUrl: string = 'https://localhost';

    constructor(private httpClient: HttpClient) {
    }

    listStories(): Observable<Array<Issue>> {
        return this.httpClient.get<Array<IssueResponse>>(`${this.baseUrl}/stories`, {
            observe: 'body',
            responseType: 'json'
        }).pipe(map((response) => response.map(issueResponseToIssue)));
    }

    getStory(id: string): Observable<Issue> {
        return this.httpClient.get<IssueResponse>(`${this.baseUrl}/stories/${id}`, {
            observe: 'body',
            responseType: 'json'
        }).pipe(map(issueResponseToIssue));
    }

    createStory(request: CreateIssueRequest): Observable<CreateStoryResponse> {
        return this.httpClient.post<CreateStoryResponse>(`${this.baseUrl}/stories`, request, {
            observe: 'body',
            responseType: 'json'
        });
    }

}
