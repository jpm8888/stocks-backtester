import React, {Component} from 'react';

class Th extends Component {
    render() {
        return (
            <th style={{textAlign: 'center'}}>{this.props.children}</th>
        );
    }
}

export default Th;
