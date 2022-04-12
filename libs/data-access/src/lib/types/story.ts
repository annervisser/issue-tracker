import type { Temporal } from '@js-temporal/polyfill';
import { State } from './state';

// TODO Make Issue immutable, immer?
export interface Story {
    title: string;
    id: string;
    createdAt: Temporal.Instant;
    state: State;
}
