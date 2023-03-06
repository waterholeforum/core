import { Controller } from '@hotwired/stimulus';
import uPlot from 'uplot';
import 'uplot/dist/uPlot.min.css';

/**
 * Controller for the line-chart dashboard widget.
 *
 * @internal
 */
export default class extends Controller {
    static targets = [
        'table',
        'chart',
        'summary',
        'legend',
        'legendAmount',
        'legendPeriod',
        'axis',
    ];

    declare readonly tableTarget: HTMLTableElement;
    declare readonly chartTarget: HTMLElement;
    declare readonly summaryTarget: HTMLElement;
    declare readonly legendTarget: HTMLElement;
    declare readonly legendAmountTarget: HTMLElement;
    declare readonly legendPeriodTarget: HTMLElement;
    declare readonly axisTarget: HTMLElement;

    observer?: ResizeObserver;
    uplot?: uPlot;

    private getSize() {
        return {
            width: this.chartTarget.offsetWidth,
            height: this.chartTarget.offsetHeight,
        };
    }

    connect() {
        this.tableTarget.hidden = true;
        this.chartTarget.hidden = false;
        this.axisTarget.hidden = false;

        this.observer = new ResizeObserver(() => {
            this.uplot?.setSize(this.getSize());
        });

        this.observer.observe(this.chartTarget!);

        const cs = getComputedStyle(document.documentElement);

        const options: uPlot.Options = {
            ...this.getSize(),
            padding: [1, 1, 1, 1],
            series: [
                {},
                {
                    stroke: cs.getPropertyValue('--color-stroke'),
                    width: 2,
                    points: { show: false },
                },
                {
                    stroke: cs.getPropertyValue('--color-accent'),
                    width: 2,
                    points: { show: false },
                },
            ],
            cursor: {
                y: false,
            },
            axes: [{ show: false }, { show: false }],
            scales: {
                y: { range: (u, dataMin, dataMax) => [dataMin, dataMax] },
            },
            select: {
                show: false,
            } as uPlot.Select, // bug
            legend: {
                show: false,
            },
            hooks: {
                init: [
                    (u) => {
                        u.over.addEventListener('mouseenter', () => {
                            this.summaryTarget.hidden = true;
                            this.legendTarget.hidden = false;
                        });
                        u.over.addEventListener('mouseleave', () => {
                            this.summaryTarget.hidden = false;
                            this.legendTarget.hidden = true;
                        });
                    },
                ],
                setCursor: [
                    (u) => {
                        const { idx } = u.cursor;
                        this.legendAmountTarget.textContent =
                            typeof idx === 'number' ? uPlot.fmtNum(u.data[2][idx] || 0) : '';
                        this.legendPeriodTarget.textContent =
                            typeof idx === 'number' ? ths[idx].textContent : '';
                    },
                ],
            },
        };

        const ths = Array.from(this.tableTarget.querySelectorAll<HTMLElement>('thead th')).slice(1);

        const data: uPlot.AlignedData = [
            ths.map((th) => Number(th.dataset.timestamp)),
            ...Array.from(this.tableTarget.querySelectorAll('tbody tr'))
                .reverse()
                .map((tr) =>
                    Array.from(tr.querySelectorAll('td')).map((td) => Number(td.textContent))
                ),
        ];

        this.uplot = new uPlot(options, data, this.chartTarget!);
    }

    disconnect() {
        this.observer?.disconnect();
        this.uplot?.destroy();
    }
}
