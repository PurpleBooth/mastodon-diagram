import {Component, OnInit} from '@angular/core';
import {MastodonAnalysisService} from '../mastodon-analysis.service';
import {MastodonAnalyses} from '../mastodon-analyses';
import {ChartOptions, ChartType} from 'chart.js';
import {Label} from 'ng2-charts';

@Component({
  selector: 'app-mastodon-analysis-graphs',
  templateUrl: './mastodon-analysis-graphs.component.html',
  styleUrls: ['./mastodon-analysis-graphs.component.scss']
})
export class MastodonAnalysisGraphsComponent implements OnInit {
  private analyses: MastodonAnalyses;

  public pieChartOptions: ChartOptions = {
    responsive: true,
    legend: {
      position: 'top',
    },
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          return ctx.chart.data.labels[ctx.dataIndex];
        },
      },
    }
  };


  public pieChartType: ChartType = 'pie';
  public pieChartLegend = true;
  public pieChartPlugins = [];
  public pieChartColors = [];
  public pieCharts: {
    name: string
    pieChartLabels: Label[],
    pieChartData: number[]
  }[] = [];

  constructor(private analysisService: MastodonAnalysisService) {
  }

  ngOnInit(): void {
    this.getAnalyses();
  }

  getAnalyses() {
    this.analysisService
      .getAnalyses()
      .subscribe(analyses => {
        this.pieCharts = Object.entries(analyses).map(
          ([name, analysis]) => ({
            name,
            pieChartLabels: Object.keys(analysis),
            pieChartData: Object.values(analysis)
          })
        );
      });
  }
}
