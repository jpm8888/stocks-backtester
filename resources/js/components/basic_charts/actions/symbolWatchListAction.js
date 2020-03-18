import {ON_FETCH_FNO_SYMBOLS, ON_FILTER} from "./types";
import store from '../store';

export const download_fno_symbols = () => (dispatch) => {
    fetch('/fetch/fno_stocks')
        .then((res) => res.json()
            .then((res) => {
                dispatch({type: ON_FETCH_FNO_SYMBOLS, payload: res});
            }));
};

export const on_filter = (name, value) => (dispatch) => {

    let reducer = store.getState().symbolWatchListReducer;
    let symbols = reducer.fno_symbols;

    let q = value;
    let str = (q) ? q.trim().toUpperCase() : '';

    let filtered;
    if (str === ''){
        filtered = symbols;
    }else{
        filtered = symbols.filter((item => {
            if (item.symbol.match(str)){
                return item;
            }
        }));
    }

    dispatch({type: ON_FILTER, payload: {
        name : name,
        value : str,
        filteredSymbols : filtered,
    }});
};
