import React, {Component} from 'react';
import ComponentCard from "./ComponentCard";

class ComponentLoading extends Component {
    render() {
        return (
            <ComponentCard label={"Please wait..."}>
                <div className="spinner-border text-dark" style={{display: 'block', margin: 'auto'}}></div>
            </ComponentCard>
        );
    }
}

export default ComponentLoading;
