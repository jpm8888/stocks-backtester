import React, {Component} from 'react';

class Th extends Component {
    render() {
        return (
            <th style={{textAlign : 'center'}}>{this.props.val}</th>
        );
    }
}

export default Th;