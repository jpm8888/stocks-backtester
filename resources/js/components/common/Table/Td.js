import React, {Component} from 'react';

class Td extends Component {
    render() {
        return (
            <td style={{textAlign : 'center'}}>{this.props.val}</td>
        );
    }
}

export default Td;