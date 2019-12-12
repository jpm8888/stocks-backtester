import React, {Component} from 'react';

class Th extends Component {
    render() {
        const backgroundColor = (this.props.backgroundColor) ? this.props.backgroundColor : "white";
        const color = (this.props.color) ? this.props.color : "black";
        return (
            <th  style={{textAlign: 'center', backgroundColor : backgroundColor, color: color}}>{this.props.children}</th>
        );
    }
}

export default Th;
