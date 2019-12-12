import React, {Component} from 'react';

class Td extends Component {
    render() {
        const backgroundColor = (this.props.backgroundColor) ? this.props.backgroundColor : "white";
        const color = (this.props.color) ? this.props.color : "black";
        return (
            <td style={{textAlign: 'center', backgroundColor : backgroundColor, color: color}}>{this.props.children}</td>
        );
    }
}

export default Td;
