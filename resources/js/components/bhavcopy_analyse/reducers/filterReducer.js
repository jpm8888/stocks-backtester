import {FETCH_PARAMS, ON_CHANGE_INDEX, ON_CHANGE_SYMBOL_SELECT, SET_LOADING} from "../actions/types";

const initialState = {
    is_loading : false,
    indices : [{name : 'NIFTY', id : 'nifty'}, {name : 'BANKNIFTY', id : 'banknifty'}],
    index : {},
    banknifty : [],
    nifty : [],
    symbols : [],

    s_symbol : '',
    s_stock : {},
    data : [],
};


export default function (state = initialState, action) {
    switch (action.type) {
        case FETCH_PARAMS:
            return{
                ...state,
                banknifty: action.payload.banknifty,
                nifty: action.payload.nifty
            };
        case ON_CHANGE_INDEX:
            let symbols = [];
            if ('nifty' === action.payload.value)
                symbols = [...state.nifty];
            else symbols = [...state.banknifty];
            return {
                ...state,
                index : action.payload,
                symbols : symbols,
            }
        case SET_LOADING:
            return{
                ...state,
                is_loading : action.payload,
            };
        case ON_CHANGE_SYMBOL_SELECT:
            return{
                ...state,
                s_stock : action.payload.selected,
                s_symbol : (action.payload.selected.label) ? action.payload.selected.label : '',
                data : action.payload.res,
            };
        default : return state;
    }
}

