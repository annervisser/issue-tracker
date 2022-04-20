import { BehaviorSubject, distinctUntilChanged, map, Observable, shareReplay } from 'rxjs';
import produce, { applyPatches, Draft, freeze, Immutable, produceWithPatches } from 'immer';
import { Inject, Injectable, OnDestroy, Optional } from '@angular/core';


type StateOf<InitialState extends object> = /*InitialState extends Immutable<InitialState> ? InitialState : */Immutable<InitialState>;

@Injectable()
abstract class _ComponentState<InitialState extends object, STATE = StateOf<InitialState>> implements OnDestroy {
    private readonly stateSubject: BehaviorSubject<STATE>;

    protected get state(): STATE {
        return this.stateSubject.value;
    }

    private set state(value: STATE) {
        this.stateSubject.next(freeze(value));
    }

    constructor(@Optional() @Inject('This class should never be injected directly') initialState: STATE) {
        this.stateSubject = new BehaviorSubject<STATE>(initialState);
    }

    protected setState(newState: STATE): void {
        this.state = newState;
    }

    protected patchState(patch: Partial<STATE>): void {
        this.updateState((state) => Object.assign(state, patch));
    }

    protected updateState(updateFn: (draft: Draft<STATE>) => Draft<STATE> | void): void {
        this.state = produce(this.state, updateFn);
    }

    protected optimisticUpdate(updateFn: (draft: Draft<STATE>) => void): { confirm: () => void; revert: () => void } {
        const [newState, _, inversePatches] = produceWithPatches(this.state, updateFn);
        this.state = newState;
        const revert = () => this.state = applyPatches(this.state, inversePatches);
        const confirm = () => undefined;
        return { revert, confirm };
    }

    protected select$<T>(selector: (state: STATE) => T): Observable<T> {
        return this.stateSubject
            .asObservable()
            .pipe(
                map((state) => selector(state)),
                distinctUntilChanged(),
                shareReplay({ bufferSize: 1, refCount: true })
            );
    }

    public ngOnDestroy(): void {
        this.stateSubject.complete();
    }
}

export abstract class ComponentState<T extends object> extends _ComponentState<T> {

}
