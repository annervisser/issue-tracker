import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
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

    listStates(): Observable<Array<State>> {
        return this.httpClient.get<Array<StateResponse>>(`${this.baseUrl}/states`)
            .pipe(map((response) => response.map(stateResponseToState)));
    }

    getState(id: string): Observable<State> {
        return this.httpClient.get<StateResponse>(`${this.baseUrl}/stories/${id}`)
            .pipe(map(stateResponseToState));
    }
}
