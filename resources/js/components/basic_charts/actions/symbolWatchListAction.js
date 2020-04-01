import {ON_FETCH_FNO_SYMBOLS, ON_FILTER, SET_LOADING_ON_LIKE} from "./types";
import store from '../store';
import axios from "axios";

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


export const onToggleFavorite = (idx, symbol) => (dispatch) => {
    dispatch({type: SET_LOADING_ON_LIKE, payload: {idx : idx, loading : true}});
    axios.post('/fetch/toggle_favorite', {symbol: symbol})
        .then((response) => {
            const data = response.data;
            if (data.status === 1) {
                let fav_id = data.fav_id;
                let reducer = store.getState().symbolWatchListReducer;
                let symbols = reducer.fno_symbols;
                symbols.map((item, index)=>{
                   if (index === idx) item.fav_id = fav_id;
                });
                dispatch({type: ON_FETCH_FNO_SYMBOLS, payload: symbols});
            }
            dispatch({type: SET_LOADING_ON_LIKE, payload: {idx : idx, loading : false}});
        });
}
