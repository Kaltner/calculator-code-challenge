import React, {Component} from 'react';
import PropTypes from 'prop-types';
import ControlPanel from './ControlPanel';
import DisplayValue from './DisplayValue';
import IosBackspaceOutline from 'react-ionicons/lib/IosBackspaceOutline';
import IosClose from 'react-ionicons/lib/IosClose';
import '../../stylesheets/display.scss';

class Panel extends Component {
    static propTypes = {
        state: PropTypes.object,
        calculate: PropTypes.func,
        addToCalculation: PropTypes.func,
        changeFocus: PropTypes.func,
        reset: PropTypes.func,
        backspace: PropTypes.func
    }

    renderDisplay = () => {
        return (
            this.props.state.calculation.map((val) => {
                console.log("VAL!!!", val);
                // console.log("I!!!", i);
                // return val
                // <DisplayValue />
            })
        )
    }
    render() {
        const iconOptions = {
            color: "#FFF",
            fontSize: "1.2em"
        }
        return (
            <div className="row">
                <div className="col-12 visor p-3 mb-2 text-light bg-dark">
                    <div className="display">
                        {this.props.state.calculation.map((val, i) => (
                            <DisplayValue 
                                key={i}
                                calc={{op: val, index: i}}
                                current_focus={this.props.state.focus}
                            />
                        ))}
                        <span className="h4 float-right icon">
                            <IosClose {...iconOptions} onClick={() => this.props.reset()}/>
                        </span>
                        <span className="h4 float-right icon">
                            <IosBackspaceOutline {...iconOptions} onClick={() => this.props.backspace()}/>
                        </span>
                        <span className="h4 float-right icon">
                            {this.props.state.calc_total}
                        </span>
                    </div>
                </div>
                <ControlPanel 
                    state={this.props.state}
                    calculate={this.props.calculate}
                    addToCalculation={this.props.addToCalculation}
                />
            </div>  
        );
    }
}

export default Panel;