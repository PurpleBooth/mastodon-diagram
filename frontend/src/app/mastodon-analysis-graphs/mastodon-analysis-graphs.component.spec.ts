import {async, ComponentFixture, TestBed} from '@angular/core/testing';

import {MastodonAnalysisGraphsComponent} from './mastodon-analysis-graphs.component';
import {MastodonAnalysisService} from '../mastodon-analysis.service';
import {MockMastodonAnalysisService} from '../mock-mastodon-analysis.service';
import {ChartsModule} from 'ng2-charts';
import {AmplifyService} from 'aws-amplify-angular';
import {HttpClientModule} from '@angular/common/http';

describe('MastodonAnalysisGraphsComponent', () => {
  let component: MastodonAnalysisGraphsComponent;
  let fixture: ComponentFixture<MastodonAnalysisGraphsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [MastodonAnalysisGraphsComponent],
      providers: [
        AmplifyService,
        MastodonAnalysisGraphsComponent,
        {provide: MastodonAnalysisService, useClass: MockMastodonAnalysisService}
      ],
      imports: [ChartsModule, HttpClientModule,
      ]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MastodonAnalysisGraphsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should render a graph', () => {
    const compiled = fixture.nativeElement;
    expect(compiled.querySelector('.graph')).toBeTruthy('Graph present');
  });

  it('should one graph per file in s3', () => {
    const compiled = fixture.nativeElement;
    expect(compiled.querySelectorAll('.graph').length).toEqual(2);
  });
});
