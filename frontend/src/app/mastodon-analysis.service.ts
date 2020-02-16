import {Injectable} from '@angular/core';
import {defer, Observable} from 'rxjs';
import {MastodonAnalyses} from './mastodon-analyses';
import {AmplifyService} from 'aws-amplify-angular';
import {StorageClass} from 'aws-amplify';
import {HttpClient} from '@angular/common/http';
import {MastodonAnalysis} from './mastodon-analysis';

export interface MastodonAnalysisServiceInterface {
  getAnalyses(): Observable<MastodonAnalyses>;
}

@Injectable({
  providedIn: 'root'
})
export class MastodonAnalysisService implements MastodonAnalysisServiceInterface {

  private storage: StorageClass;

  constructor(private http: HttpClient, private amplifyService: AmplifyService) {
    this.storage = amplifyService.storage();
  }

  getAnalyses(): Observable<MastodonAnalyses> {
    return defer(async (): Promise<MastodonAnalyses> => {
      const objectKeys = await this.storage.list('', {level: 'public'});
      const urls: string[] = await Promise.all(
        objectKeys.map(
          objects => this.storage.get(objects.key)
        )
      );
      const mastodonAnalyses = await Promise.all(
        urls.map(
          url => {
            return this.http.get<MastodonAnalysis>(url).toPromise();
          }
        )
      );

      return new Promise(
        resolve => {
        resolve(
          mastodonAnalyses.reduce(
            (previousValue: MastodonAnalyses, body: MastodonAnalysis, currentIndex: number) => {
              previousValue[objectKeys[currentIndex].key] = body;
              return previousValue;
            },
            new MastodonAnalyses()
          )
        );
      }
      );
    });
  }
}
