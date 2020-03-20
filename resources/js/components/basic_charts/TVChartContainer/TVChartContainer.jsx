import React, {Component} from 'react';
import './index.css';
import {widget} from "../../../charting_library/charting_library.min";
import connect from "react-redux/es/connect/connect";
import {bindActionCreators} from "redux";
import {setWidget} from "../actions/indexActions";
import {FutureCoi} from "./indicators/fut_coi/code";
import {Equity} from "./indicators/equity/code";
import {configFutureCoi} from "./indicators/fut_coi/meta";


function getLanguageFromURL() {
	const regex = new RegExp('[\\?&]lang=([^&#]*)');
	const results = regex.exec(window.location.search);
	return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

class TVChartContainer extends Component {
	static defaultProps = {
		symbol: 'AXISBANK',
		interval: 'D',
        toolbar_bg: '#f4f7f9',
		containerId: 'tv_chart_container',
		datafeedUrl: 'api', //path of the url without trailing slash
		libraryPath: '/charting_library/',
		chartsStorageUrl: 'https://saveload.tradingview.com',
		chartsStorageApiVersion: '1.1',
		clientId: 'tradingview.com',
		userId: 'public_user_id',
		fullscreen: false,
		autosize: true,
		studiesOverrides: {},
        supported_resolutions: ['1D', '1W', '1M'],
        time_frames: [
            { text: "100y", resolution: "M", description: "100 Years", title: "100yr" },
            { text: "3y", resolution: "W", description: "3 Years", title: "3yr" },
            { text: "1y", resolution: "D", description: "1 Year", title: "1yr" },
            { text: "9m", resolution: "D", description: "9 Months", title: "9m" },
            { text: "6m", resolution: "D", description: "6 Months", title: "6m" },
            { text: "3m", resolution: "D", description: "3 Months", title: "3m" },
        ],
	};

	tvWidget = null;

	componentDidMount() {
		const widgetOptions = {
			symbol: this.props.symbol,
            time_frames : this.props.time_frames,
			datafeed: new window.Datafeeds.UDFCompatibleDatafeed(this.props.datafeedUrl, (60 * 1000)),
			interval: this.props.interval,
			container_id: this.props.containerId,
			library_path: this.props.libraryPath,
            timezone: "Asia/Kolkata",
			locale: getLanguageFromURL() || 'en',
			disabled_features: ['use_localstorage_for_settings'],
			enabled_features: ['study_templates'],
			charts_storage_url: this.props.chartsStorageUrl,
			charts_storage_api_version: this.props.chartsStorageApiVersion,
			client_id: this.props.clientId,
			user_id: this.props.userId,
			fullscreen: this.props.fullscreen,
			autosize: this.props.autosize,
			studies_overrides: this.props.studiesOverrides,
            symbol_search_request_delay : (1 * 1000), //2 seconds delay after type
            debug: true,
            custom_indicators_getter: function(PineJS) {
                Equity.prototype.PineJS = PineJS;
                FutureCoi.prototype.PineJS = PineJS;
                return Promise.resolve([
					// { name: "Equity", metainfo : configEquity, constructor : Equity},
					{ name: "FutureCOI", metainfo : configFutureCoi, constructor : FutureCoi},
                ]);
            },
		};

		const tvWidget = new widget(widgetOptions);
		this.tvWidget = tvWidget;
		this.props.setWidget(tvWidget);

		tvWidget.onChartReady(() => {
            // tvWidget.activeChart().createStudy('Equity', false, true);
            tvWidget.activeChart().createStudy('FutureCOI', false, true);

			tvWidget.headerReady().then(() => {

			});
		});
	}

	componentWillUnmount() {
		if (this.tvWidget !== null) {
			this.tvWidget.remove();
			this.tvWidget = null;
		}
	}

	render() {
		return (
			<div
				id={ this.props.containerId }
				className={ 'TVChartContainer' }
			/>
		);
	}
}

const mapStateToProps = (state) => {
    const s = state.indexReducer;
    return {
        // mode: s.mode,
        // amount: s.amount,
        // ref_num: s.ref_num,
        // verified: s.verified,
        // msg_type: s.msg_type,
        // msg: s.msg,
        // is_loading: s.is_loading,
        // user_id: s.user_id,
        // user_balance: s.user_balance,
        // player_id: s.player_id,
        // player_name: s.player_name,
        // is_fetching_transactions: s.is_fetching_transactions,
        // transactions: s.transactions,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
		setWidget: (widget) => setWidget(widget),

        // verify_user: (user_id) => verify_user(user_id),
        // add_transaction: (player_id, amount, type, remarks) => add_transaction(player_id, amount, type, remarks),
        // reset: () => reset()
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(TVChartContainer);
