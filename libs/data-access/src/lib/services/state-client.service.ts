import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { firstValueFrom, map } from 'rxjs';
import { State } from '../types/state';

interface StateResponse {
    id: string;
    name: string;
}

const stateResponseToState = (response: StateResponse): State => {
    return ({ ...response });
};

@Injectable({ providedIn: 'root' })
export class StateClient {
    private readonly baseUrl: string = 'https://localhost';

    constructor(private httpClient: HttpClient) {
    }

    listStates(): Promise<Array<State>> {
        let response$ = this.httpClient.get<Array<StateResponse>>(`${this.baseUrl}/states`)
            .pipe(map((response) => response.map(stateResponseToState)));
        return firstValueFrom(response$);
    }

    getState(id: string): Promise<State> {
        let response$ = this.httpClient.get<StateResponse>(`${this.baseUrl}/stories/${id}`)
            .pipe(map(stateResponseToState));
        return firstValueFrom(response$);
    }
}
