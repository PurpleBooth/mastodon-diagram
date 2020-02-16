import { BrowserModule } from '@angular/platform-browser';
import {FormsModule} from '@angular/forms';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { ChartsModule } from 'ng2-charts';
import { AmplifyAngularModule, AmplifyService } from 'aws-amplify-angular';
import { MastodonAnalysisGraphsComponent } from './mastodon-analysis-graphs/mastodon-analysis-graphs.component';
import {HttpClientModule} from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    MastodonAnalysisGraphsComponent
  ],
  imports: [
    FormsModule,
    BrowserModule,
    HttpClientModule,
    ChartsModule,
    AmplifyAngularModule,
  ],
  providers: [AmplifyService],
  bootstrap: [AppComponent]
})
export class AppModule { }
