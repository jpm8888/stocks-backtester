import {ON_CHANGE, ON_CHART_CREATED} from "../actions/types";

const initialState = {
    is_loading: false,
    widget: undefined,
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
        default : return state;
    }
}
