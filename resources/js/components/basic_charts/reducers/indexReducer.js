import {ON_CHANGE, ON_CHART_CREATED, ON_FETCH_APP_INFO, ON_SYMBOL_SELECTED} from "../actions/types";

const initialState = {
    is_loading: false,
    widget: undefined,
    selectedSymbol : '',

    debug : false,
};


export default function (state = initialState, action) {
    switch (action.type) {
        case ON_CHANGE:
            return {
                ...state,
                [action.payload.name]: action.payload.value,
            };
        case ON_CHART_CREATED:
            return {
                ...state,
                widget: action.payload.widget,
            };
        case ON_SYMBOL_SELECTED:
            return {
                ...state,
                selectedSymbol: action.payload,
            };
        case ON_FETCH_APP_INFO:
            return {
                ...state,
                debug: action.payload.debug,
            };
        default : return state;
    }
}
