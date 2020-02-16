import {Observable} from 'rxjs';
import {MastodonAnalyses} from './mastodon-analyses';
import {MastodonAnalysisServiceInterface} from './mastodon-analysis.service';


export class MockMastodonAnalysisService implements MastodonAnalysisServiceInterface {
  public getAnalyses(): Observable<MastodonAnalyses> {
    const values: Array<MastodonAnalyses> = [
      {
        '7161ef05-httpsmastodon.social': {
          'pawoo.net': 4,
          'mstdn-workers.com': 1,
          'fedibird.com': 1,
          'otogamer.me': 1,
          'best-friends.chat': 1,
          'mstdn.kemono-friends.info': 2,
          'imastodon.net': 1,
          'mastodont.cat': 1,
          'mastodon.social': 2,
          'desu.social': 1,
          'social.mikutter.hachune.net': 1,
          'mstdn.jp': 2,
          'mast.moe': 1,
          'mstdn.klamath.jp': 1
        },
        '7161ef05-testing.social': {
          'pawoo.net': 4,
          'mstdn-workers.com': 1,
          'fedibird.com': 1,
          'otogamer.me': 1,
          'best-friends.chat': 1,
          'mstdn.kemono-friends.info': 2,
          'imastodon.net': 1,
          'mastodont.cat': 1,
          'mastodon.social': 2,
          'desu.social': 1,
          'social.mikutter.hachune.net': 1,
          'mstdn.jp': 2,
          'mast.moe': 1,
          'mstdn.klamath.jp': 1
        }
      }
    ];

    return new Observable<{ [p: string]: { [p: string]: number } }>(subscriber => {
      subscriber.next(values.pop());
    });
  }
}

