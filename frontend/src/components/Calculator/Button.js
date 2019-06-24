import React, {Component} from 'react';
import PropTypes from 'prop-types';

class Button extends Component {
    static propTypes = {
        operator: PropTypes.string,   
        function: PropTypes.string,   
        state: PropTypes.object,
        addToCalculation: PropTypes.func,
        calculate: PropTypes.func
    }

    addValue(num, temp_state) {
        // If its odd, It is in a place where there must be an opeator, so we go a step forward in the array
        if(temp_state.focus % 2 === 1) temp_state.focus = temp_state.focus + 1;
        
        // If the input is zero, we have some rules to avoid calculations with double zero or a zero in the left
        if(num === "0" && (temp_state.calculation[temp_state.focus] === "0" || temp_state.calculation[temp_state.focus] === "-")) return false;
        
        // If the current position of the calculation is undefined or zero, we can just put the number there
        if(temp_state.calculation[temp_state.focus] === undefined || temp_state.calculation[temp_state.focus] === "0") {
            temp_state.calculation[temp_state.focus] = num;
            return temp_state;
        }
        
        // Otherwise, we just concat the number we want with what is there currently
        temp_state.calculation[temp_state.focus] = temp_state.calculation[temp_state.focus].concat(num);
        return temp_state    
    }

    operator(op, temp_state) {
        if(temp_state.focus > 1) return false; //REMOVE THIS TO STOP LIMITING TO 2 numbers and 1 opeartor 
        
        // If the position is even, the input is in a place where a number probably is
        if(temp_state.focus % 2 === 0) {
            temp_state.focus = temp_state.focus + 1;
        }

        temp_state.calculation[temp_state.focus] = op;
        // Go to the next step and set it with 0
        temp_state.focus = temp_state.focus + 1;
        return temp_state;
    }

    changeToFloat(op, temp_state) {
        // If the position is not even, then this operator doesn't work
        if(temp_state.focus % 2 === 1) return false;
        
        // If the position is not undefined, the operator can concat..;
        if(temp_state.calculation[temp_state.focus] !== undefined) {
            // ... unless there is already a "." there
            if(temp_state.calculation[temp_state.focus].includes('.')) return false;

            temp_state.calculation[temp_state.focus] = temp_state.calculation[temp_state.focus].concat(op);
            return temp_state;
        }

        return false;
    }

    handleOperation = () => {
        const func = this.props.function;
        // If its the calculate function, just send it;
        if(func === "calculate") {
            this.props.calculate();
            return false;
        }
        const temp_state = {...this.props.state};
        const operator = this.props.operator;

        // Call to function defined in the ControlPanel.js
        this.props.addToCalculation(this[func](operator, temp_state))
    }

    render() {
        return (
            <button 
                className="btn btn-lg btn-block btn-outline-secondary rounded-pill"
                onClick={this.handleOperation}
            >
                {this.props.operator}
            </button>
        );
    }
}

export default Button;