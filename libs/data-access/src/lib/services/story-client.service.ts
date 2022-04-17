import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { firstValueFrom, map } from 'rxjs';
import { Story } from '../types/story';
import { Temporal } from '@js-temporal/polyfill';
import { State } from '../types/state';
import { mapArray } from '../utils';

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

    listStories(): Promise<Array<Story>> {
        let response$ = this.httpClient.get<Array<StoryResponse>>(`${this.baseUrl}`)
            .pipe(map(mapArray(storyResponseToStory)));
        return firstValueFrom(response$);
    }

    listStoriesInState(stateId: string): Promise<Array<Story>> {
        let response$ = this.httpClient.get<Array<StoryResponse>>(`${this.baseUrl}/state/${stateId}`)
            .pipe(map(mapArray(storyResponseToStory)));
        return firstValueFrom(response$);
    }

    getStory(id: string): Promise<Story> {
        let response$ = this.httpClient.get<StoryResponse>(`${this.baseUrl}/${id}`)
            .pipe(map(storyResponseToStory));
        return firstValueFrom(response$);
    }

    createStory(request: CreateStoryRequest): Promise<CreateStoryResponse> {
        let response$ = this.httpClient.post<CreateStoryResponse>(`${this.baseUrl}`, request);
        return firstValueFrom(response$);
    }

    deleteStory(id: string): Promise<void> {
        let response$ = this.httpClient.delete<void>(`${this.baseUrl}/${id}`);
        return firstValueFrom(response$);
    }
}
