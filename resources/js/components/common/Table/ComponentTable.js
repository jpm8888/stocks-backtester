import React, {Component} from 'react';

class ComponentTable extends Component {

    render() {
        const style = (this.props.style) ? this.props.style : {overflowX : 'auto', overflowY : 'scroll', fontSize : 11, whiteSpace : 'nowrap'};

        return (
            <div className="col-md-12" style={style}>
                <table className="table table-bordered table-sm">
                    <tbody>
                    {this.props.children}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default ComponentTable;
