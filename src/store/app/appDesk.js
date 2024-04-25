import chart from "@images/cards/chart-success.png";
import card from "@images/cards/credit-card-primary.png";
import paypal from "@images/cards/paypal-error.png";
import wallet from "@images/cards/wallet-info.png";
import { defineAsyncComponent } from 'vue'

export default [
	{"x":0,"y":0,"w":4,"h":2,"i":"congrats", component: 'AnalyticsCongratulations'},
	{"x":0,"y":0,"w":4,"h":6,"i":"total", component: 'AnalyticsTotalRevenue'},
	{"x":4,"y":0,"w":1,"h":3,"i":"profitCard",
		component: 'CardStatisticsVertical',
		props: { title: 'Profit', image: chart, stats: '$12,628', change: 72.80, },
	},
	{"x":5,"y":0,"w":1,"h":3,"i":"salesCard",
		component: 'CardStatisticsVertical',
		props: { title: 'Sales', image: wallet, stats: '$4,679', change: 28.42, },
	},
	{"x":4,"y":3,"w":1,"h":3,"i":"paymentsCard",
		component: 'CardStatisticsVertical',
		props: { title: 'Payments', image: paypal, stats: '$2,468', change: -14.82, },
	},
	{"x":5,"y":3,"w":1,"h":3,"i":"transactionsCard",
		component: 'CardStatisticsVertical',
		props: { title: 'Transactions', image: card, stats: '$14,857', change: 28.14, },
	},
	{"x":4,"y":6,"w":2,"h":2,"i":"profit", component: 'AnalyticsProfitReport'},
	{"x":0,"y":8,"w":2,"h":6,"i":"orders", component: 'AnalyticsOrderStatistics'},
	{"x":2,"y":8,"w":2,"h":6,"i":"finance", component: 'AnalyticsFinanceTabs'},
	{"x":4,"y":8,"w":2,"h":5,"i":"transactions", component: 'AnalyticsTransactions'},
]