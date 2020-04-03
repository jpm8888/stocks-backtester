import {
    LIST_FAVS,
    LIST_FNO,
    ON_FAV_DONE,
    ON_FETCH_FNO_SYMBOLS,
    ON_FILTER,
    ON_SELECT_LIST,
    SET_LOADING_ON_LIKE
} from "./types";
import store from '../store';
import axios from "axios";

export const download_fno_symbols = () => (dispatch) => {
    fetch_fno_symbols(dispatch);
};

function fetch_fno_symbols(dispatch) {
    fetch('/fetch/fno_stocks/1') //1 -> type_fno
        .then((res) => res.json()
            .then((res) => {
                dispatch({type: ON_FETCH_FNO_SYMBOLS, payload: res});
            }));
}

function fetch_fav_symbols(dispatch) {
    fetch('/fetch/favorites_stocks/1') //1 -> type_fno
        .then((res) => res.json()
            .then((res) => {
                dispatch({type: ON_FETCH_FNO_SYMBOLS, payload: res});
            }));
}

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


export const onToggleFavorite = (idx, symbol) => (dispatch) => {
    dispatch({type: SET_LOADING_ON_LIKE, payload: {idx : idx, loading : true}});
    axios.post('/fetch/toggle_favorite', {symbol: symbol, type : 1}) //1 -> type_fno
        .then((response) => {
            const data = response.data;
            if (data.status === 1) {
                let fav_id = data.fav_id;
                let reducer = store.getState().symbolWatchListReducer;
                let symbols = reducer.fno_symbols;
                let queryStr = reducer.queryStr;
                symbols.map((item, index)=>{
                   if (symbol === item.symbol) item.fav_id = fav_id;
                });

                let filtered;
                if (queryStr === ''){
                    filtered = symbols;
                }else{
                    filtered = symbols.filter((item => {
                        if (item.symbol.match(queryStr)){
                            return item;
                        }
                    }));
                }

                dispatch({type: ON_FAV_DONE, payload: {symbols : symbols, filtered : filtered}});
            }
            dispatch({type: SET_LOADING_ON_LIKE, payload: {idx : idx, loading : false}});
        });
}

export const onListSelected = (type) => (dispatch) => {
    switch (type) {
        case LIST_FNO:
            fetch_fno_symbols(dispatch);
            dispatch({type: ON_SELECT_LIST, payload: LIST_FNO});
            break;
        case LIST_FAVS:
            fetch_fav_symbols(dispatch)
            dispatch({type: ON_SELECT_LIST, payload: LIST_FAVS});
            break;
    }
};
