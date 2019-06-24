import React, {Component} from 'react';
import Button from './Button';
import PropTypes from 'prop-types';

class ControlPanel extends Component {
    static propTypes = {
        state: PropTypes.object,
        calculate: PropTypes.func,
        addToCalculation: PropTypes.func
    }

    render() {
        const buttons = [
            [
                {
                    "operator": "7",
                    "function": "addValue",
                },
                {
                    "operator": "8",
                    "function": "addValue",
                },
                {
                    "operator": "9",
                    "function": "addValue",
                },
            ],
            [
                {
                    "operator": "4",
                    "function": "addValue",
                },
                {
                    "operator": "5",
                    "function": "addValue",
                },
                {
                    "operator": "6",
                    "function": "addValue",
                },
            ],
            [
                {
                    "operator": "1",
                    "function": "addValue",
                },
                {
                    "operator": "2",
                    "function": "addValue",
                },
                {
                    "operator": "3",
                    "function": "addValue",
                },
                {
                    "operator": "-",
                    "function": "operator",
                },
            ],
            [
                {
                    "operator": "0",
                    "function": "addValue",
                },
                {
                    "operator": ".",
                    "function": "changeToFloat",
                },
                {
                    "operator": "=",
                    "function": "calculate",
                },
                {
                    "operator": "+",
                    "function": "operator",
                },
            ]
        ]
        return (
            <div className="col-12 control-panel">
                {buttons.map((arr, i) => (
                    <div key={`button${i}`} className="row">
                        {arr.map((btn) => (
                            <div key={btn.operator} className="col-3">
                                <Button 
                                    key={btn.operator}
                                    operator={btn.operator} 
                                    function={btn.function}
                                    state={this.props.state}
                                    calculate={this.props.calculate}
                                    addToCalculation={this.props.addToCalculation}
                                />
                            </div>        
                        ))}
                    </div>    
                ))}
            </div>  
        );
    }
}

export default ControlPanel;