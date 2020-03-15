import {ON_CALC_COMPLETE, ON_CHANGE} from "../actions/types";
import React from "react";

const initialState = {
    capital_amount : 0,
    entry : 0,
    stop_loss : 0,
    risk_pct : 0,
    target_pct : 0,
    target_amount : 0,
    qty : 0,
    expected_profit : 0,
    expected_loss : 0,
};


export default function (state = initialState, action) {
    switch (action.type) {
        case ON_CHANGE:
            return{
                ...state,
                [action.payload.name] : action.payload.value
            };
        case ON_CALC_COMPLETE:
            const p = action.payload;
            return{
                ...state,
                risk_pct : p.risk_pct,
                target_pct : p.target_pct,
                target_amount : p.target_amount,
                qty : p.qty,
                expected_profit : p.expected_profit,
                expected_loss : p.expected_loss,
            };
        default : return state;
    }
}
