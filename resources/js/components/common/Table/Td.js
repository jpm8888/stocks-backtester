import React, {Component} from 'react';

class Td extends Component {
    render() {
        return (
            <td style={{textAlign: 'center'}}>{this.props.children}</td>
        );
    }
}

export default Td;
