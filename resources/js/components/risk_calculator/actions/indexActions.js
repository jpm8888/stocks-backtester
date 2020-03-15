import {ON_CALC_COMPLETE, ON_CHANGE} from "./types";
import store from '../store'

export const on_change = (name, value) => (dispatch) =>{
    dispatch({
        type : ON_CHANGE,
        payload : {name : name, value : value}
    });

    const state = store.getState().indexReducer;

    let capital_amount = parseFloat(state.capital_amount);
    let entry = parseFloat(state.entry);
    let stop_loss = parseFloat(state.stop_loss);

    if (!(capital_amount > 0 && entry > 0 && stop_loss > 0)) return;

    let risk_pct = 1;
    let target_pct = risk_pct * 2;

    let is_short = (stop_loss > capital_amount);

    let target_amount = entry + ((entry - stop_loss) * 2);
    let qty = Math.floor(capital_amount / ((entry - stop_loss) * 100));
    let expected_profit = (target_amount - entry) * qty;
    let expected_loss = (stop_loss - entry) * qty;

    dispatch({
        type : ON_CALC_COMPLETE,
        payload : {
            risk_pct,
            target_pct,
            target_amount,
            qty,
            expected_profit,
            expected_loss,
        }
    });
}
