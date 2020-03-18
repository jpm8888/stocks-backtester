import {ON_FETCH_FNO_SYMBOLS, ON_FILTER} from "../actions/types";

const initialState = {
    is_loading: false,
    queryStr : '',
    fno_symbols : [],
    filteredSymbols : [],
};


export default function (state = initialState, action) {
    switch (action.type) {
        case ON_FILTER:
            return {
                ...state,
                queryStr : action.payload.value,
                filteredSymbols : action.payload.filteredSymbols,
            };

        case ON_FETCH_FNO_SYMBOLS:
            return {
                ...state,
                fno_symbols : action.payload,
                filteredSymbols : action.payload,
            };
        default : return state;
    }
}
