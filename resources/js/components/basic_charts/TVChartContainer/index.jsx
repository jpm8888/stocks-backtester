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
	};

	tvWidget = null;

	componentDidMount() {
		const widgetOptions = {
			symbol: this.props.symbol,
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
            time_frames: [
                { text: "50y", resolution: "6M" },
                { text: "3y", resolution: "W" },
                { text: "8m", resolution: "D" },
                { text: "2m", resolution: "D" },
                { text: "1m", resolution: "60" },
                { text: "1w", resolution: "30" },
                { text: "7d", resolution: "30" },
                { text: "5d", resolution: "10" },
                { text: "3d", resolution: "10" },
                { text: "2d", resolution: "5" },
                { text: "1d", resolution: "5" }
            ],
		};

		const tvWidget = new widget(widgetOptions);
		this.tvWidget = tvWidget;

		tvWidget.onChartReady(() => {
			tvWidget.headerReady().then(() => {
				const button = tvWidget.createButton();
				button.setAttribute('title', 'Click to show a notification popup');
				button.classList.add('apply-common-tooltip');
				button.addEventListener('click', () => tvWidget.showNoticeDialog({
					title: 'Notification',
					body: 'TradingView Charting Library API works correctly',
					callback: () => {
						console.log('Noticed!');
					},
				}));

				button.innerHTML = 'Check API';
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
