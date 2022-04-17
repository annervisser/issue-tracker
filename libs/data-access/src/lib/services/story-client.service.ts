import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { Story } from '../types/story';
import { Temporal } from '@js-temporal/polyfill';
import { State } from '../types/state';

interface StoryResponse {
    id: string;
    title: string;
    createdAt: string;
    state: {
        id: string;
        name: string;
    };
}

const storyResponseToStory = (response: StoryResponse): Story => {
    return ({
        ...response,
        createdAt: Temporal.Instant.from(response.createdAt)
    });
};

interface CreateStoryRequest {
    title: string;
    stateId: State['id'];
}

interface CreateStoryResponse {
    storyId: string;
}

@Injectable({ providedIn: 'root' })
export class StoryClient {
    private readonly baseUrl: string = 'https://localhost/stories';

    constructor(private httpClient: HttpClient) {
    }

    listStories(): Observable<Array<Story>> {
        return this.httpClient.get<Array<StoryResponse>>(`${this.baseUrl}`)
            .pipe(map((response) => response.map(storyResponseToStory)));
    }

    listStoriesInState(stateId: string): Observable<Array<Story>> {
        return this.httpClient.get<Array<StoryResponse>>(`${this.baseUrl}/state/${stateId}`)
            .pipe(map((response) => response.map(storyResponseToStory)));
    }

    getStory(id: string): Observable<Story> {
        return this.httpClient.get<StoryResponse>(`${this.baseUrl}/${id}`)
            .pipe(map(storyResponseToStory));
    }

    createStory(request: CreateStoryRequest): Observable<CreateStoryResponse> {
        return this.httpClient.post<CreateStoryResponse>(`${this.baseUrl}`, request);
    }

    deleteStory(id: string): Observable<void> {
        return this.httpClient.delete<void>(`${this.baseUrl}/${id}`);
    }
}
