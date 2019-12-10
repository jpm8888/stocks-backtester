import {FETCH_PARAMS, ON_CHANGE_BNF_SELECT, SET_LOADING} from "../actions/types";

const initialState = {
    is_loading : false,
    banknifty : [],

    s_symbol : '',
    s_stock : {}
};


export default function (state = initialState, action) {
    switch (action.type) {
        case FETCH_PARAMS:
            return{
                ...state,
                banknifty: action.payload.banknifty
            };
        case SET_LOADING:
            return{
                ...state,
                is_loading : action.payload,
            };
        case ON_CHANGE_BNF_SELECT:
            return{
                ...state,
                s_stock : action.payload,
                s_symbol : (action.payload.label) ? action.payload.label : '',
            };
        default : return state;
    }
}

