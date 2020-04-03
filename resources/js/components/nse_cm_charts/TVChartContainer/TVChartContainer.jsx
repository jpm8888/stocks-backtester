import React, {Component} from 'react';
import './index.css';
import {widget} from "../../../charting_library/charting_library.min";
import connect from "react-redux/es/connect/connect";
import {bindActionCreators} from "redux";
import {fetch_app_info, setWidget} from "../actions/indexActions";


function getLanguageFromURL() {
	const regex = new RegExp('[\\?&]lang=([^&#]*)');
	const results = regex.exec(window.location.search);
	return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

class TVChartContainer extends Component {

	constructor(props){
		super(props);
	}

	static defaultProps = {
		symbol: 'AXISBANK',
        toolbar_bg: '#f9a281',
		datafeedUrl: 'nse_cm_charts/api', //path of the url without trailing slash
		libraryPath: '/charting_library/',
		chartsStorageUrl: '/api/chart_save_engine',
		chartsStorageApiVersion: 'v1',
		clientId: 'shivafno',
		// userId: this.props.user_id,
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
		this.props.fetch_app_info();
		const widgetOptions = {
			symbol: this.props.symbol,
            time_frames : this.props.time_frames,
			datafeed: new window.Datafeeds.UDFCompatibleDatafeed(this.props.datafeedUrl, (60 * 1000)),
			interval: 'D',
			container_id: 'tv_chart_container',
			library_path: this.props.libraryPath,
            timezone: "Asia/Kolkata",
			locale: getLanguageFromURL() || 'en',
			disabled_features: ['use_localstorage_for_settings'],
			enabled_features: [],
			charts_storage_url: this.props.chartsStorageUrl,
			charts_storage_api_version: this.props.chartsStorageApiVersion,
			client_id: this.props.clientId,
			user_id: this.props.user_id,
			fullscreen: false,
			autosize: true,
			studies_overrides: {},
            symbol_search_request_delay : (1 * 1000), //2 seconds delay after type
            debug: this.props.debug,
            custom_indicators_getter: function(PineJS) {

            },
		};

		const tvWidget = new widget(widgetOptions);
		this.tvWidget = tvWidget;
		this.props.setWidget(tvWidget);


		tvWidget.onChartReady(() => {

			tvWidget.getSavedCharts(function (chartRecord) {
				chartRecord.map((item)=>{
					if (item.name === 'default_cash_market'){
						tvWidget.loadChartFromServer(item);
					}
				});
			});

			tvWidget.subscribe('onAutoSaveNeeded', ()=>{
					tvWidget.saveChartToServer(function () {
				}, function () {
						console.log('on fail')
				}, {defaultChartName : 'default_cash_market', chartName : 'default_cash_market'});
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
				id={ 'tv_chart_container' }
				className={ 'TVChartContainer' }
			/>
		);
	}
}

const mapStateToProps = (state) => {
    const s = state.indexReducer;
    return {
        debug: s.debug,
    };
};

const mapDispatchToProps = (dispatch) => {
    return bindActionCreators({
		setWidget: (widget) => setWidget(widget),
		fetch_app_info: () => fetch_app_info(),
    }, dispatch);
};

export default connect(mapStateToProps, mapDispatchToProps)(TVChartContainer);
