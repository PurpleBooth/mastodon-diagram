import { TestBed, async } from '@angular/core/testing';
import { AppComponent } from './app.component';
import {BrowserModule} from '@angular/platform-browser';
import {ChartsModule} from 'ng2-charts';
import {MastodonAnalysisGraphsComponent} from './mastodon-analysis-graphs/mastodon-analysis-graphs.component';
import {AmplifyService} from 'aws-amplify-angular';
import {MastodonAnalysisService} from './mastodon-analysis.service';
import {MockMastodonAnalysisService} from './mock-mastodon-analysis.service';
import {HttpClientTestingModule, HttpTestingController} from '@angular/common/http/testing';
import {HttpClient, HttpClientModule} from '@angular/common/http';

describe('AppComponent', () => {
  let httpClient: HttpClient;
  let httpTestingController: HttpTestingController;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [
        AppComponent,
        MastodonAnalysisGraphsComponent
      ],
      providers: [
        HttpClientModule,
        AmplifyService,
        MastodonAnalysisGraphsComponent,
        {provide: MastodonAnalysisService, useClass: MockMastodonAnalysisService}
      ],
      imports: [
        HttpClientTestingModule,
        ChartsModule
      ],
    }).compileComponents();

    httpClient = TestBed.inject(HttpClient);
    httpTestingController = TestBed.inject(HttpTestingController);
  }));

  it('should create the app', () => {
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app).toBeTruthy();
  });

  it(`should have as title 'frontend'`, () => {
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('frontend');
  });

  it('should render title', () => {
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges();
    const compiled = fixture.nativeElement;
    expect(compiled.querySelector('.toolbar span').textContent).toContain('Welcome to frontend');
  });

  it('should render a graph', () => {
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges();
    const compiled = fixture.nativeElement;
    expect(compiled.querySelector('.content app-mastodon-analysis-graphs')).toBeTruthy('Graph present');
  });
});
