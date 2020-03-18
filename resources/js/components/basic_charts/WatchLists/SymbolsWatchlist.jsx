import React, {Component} from 'react';
import {bindActionCreators} from "redux";
import connect from "react-redux/es/connect/connect";
import {download_fno_symbols, on_filter} from "../actions/symbolWatchListAction";
import ComponentInput from "../../common/ComponentInput";
import {onSymbolClicked} from "../actions/indexActions";

class SymbolsWatchlist extends Component {
    componentDidMount() {
        this.props.download_fno_symbols();
    }

    render() {
        return (
            <div className="col-md-2">
                <div>
                    <ComponentInput value={this.props.queryStr} label={"Filter"} className="col" onChange={(e)=>this.props.on_filter('queryStr', e.target.value)}/>
                </div>

                <div className="col" style={{overflowX : 'auto', overflowY : 'scroll', fontSize : 11, whiteSpace : 'nowrap', height: 400, backgroundColor: 'white', marginTop : 5, padding : 5}}>
                    <table className="table table-bordered table-sm table-hover">
                            <tbody>
                            {
                                this.props.symbols.map((item, index)=>{
                                    return (
                                      <tr key={'sym_' + index} onClick={()=> this.props.onSymbolClicked(item.symbol)}>
                                          <td>{item.symbol}</td>
                                      </tr>
                                    );
                                })
                            }
                            </tbody>
                    </table>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    const s = state.symbolWatchListReducer;
    return {
        symbols: s.filteredSymbols,
        queryStr: s.queryStr,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
        download_fno_symbols : () => download_fno_symbols(),
        on_filter : (name, value) => on_filter(name, value),
        onSymbolClicked : (symbol) => onSymbolClicked(symbol),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(SymbolsWatchlist);

