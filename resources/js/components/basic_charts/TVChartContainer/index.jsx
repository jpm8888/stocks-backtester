import React, {Component} from 'react';
import './index.css';
// import {widget} from "../../../../../public/charting_library/charting_library/charting_library.min";
import {widget} from "../../../charting_library/charting_library.min";

function getLanguageFromURL() {
	const regex = new RegExp('[\\?&]lang=([^&#]*)');
	const results = regex.exec(window.location.search);
	return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

export class TVChartContainer extends Component {
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
            { text: "3y", resolution: "W", description: "3 Years", title: "3yr" },
            { text: "1y", resolution: "D", description: "1 Year", title: "1yr" },
            { text: "9m", resolution: "D", description: "9 Months", title: "9m" },
            { text: "6m", resolution: "D", description: "6 Months", title: "6m" },
            { text: "3m", resolution: "D", description: "3 Months", title: "3m" },
        ],
        // timeframe : '1D',
	};

	tvWidget = null;

	componentDidMount() {
		const widgetOptions = {
			symbol: this.props.symbol,
            time_frames : this.props.time_frames,
            // theme : 'Dark',
            //timeframe : this.props.timeframe,
			// BEWARE: no trailing slash is expected in feed URL
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
            symbol_search_request_delay : (2 * 1000), //2 seconds delay after type
            // debug: true,
		};

		const tvWidget = new widget(widgetOptions);
		this.tvWidget = tvWidget;

		tvWidget.onChartReady(() => {
			tvWidget.headerReady().then(() => {
				// const button = tvWidget.createButton();
				// button.setAttribute('title', 'Click to show a notification popup');
				// button.classList.add('apply-common-tooltip');
				// button.addEventListener('click', () => tvWidget.showNoticeDialog({
				// 	title: 'Notification',
				// 	body: 'TradingView Charting Library API works correctly',
				// 	callback: () => {
				// 		console.log('Noticed!');
				// 	},
				// }));
                //
				// button.innerHTML = 'Check API';
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
