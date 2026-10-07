<?php

declare(strict_types=1);

namespace Knppy\Ui;

/**
 * The base color schemes that can be installed into a project.
 */
enum ColorScheme: string
{
    case NEUTRAL = 'neutral';
    case ZINC = 'zinc';
    case STONE = 'stone';
    case GRAY = 'gray';

    /**
     * Get the CSS variables (light and dark theme) for this color scheme.
     */
    public function getAppCssVariables(): string
    {
        return match ($this) {
            self::NEUTRAL => $this->getNeutralScheme(),
            self::ZINC => $this->getZincScheme(),
            self::STONE => $this->getStoneScheme(),
            self::GRAY => $this->getGrayScheme(),
        };
    }

    /**
     * Get the CSS variables for the neutral color scheme.
     */
    protected function getNeutralScheme(): string
    {
        return <<<'CSS'
:root {
  --radius: 0.5rem;
  --background: oklch(1 0 0);
  --foreground: oklch(0.145 0 0);
  --card: oklch(1 0 0);
  --card-foreground: oklch(0.145 0 0);
  --popover: oklch(1 0 0);
  --popover-foreground: oklch(0.145 0 0);
  --primary: oklch(0.205 0 0);
  --primary-foreground: oklch(0.985 0 0);
  --secondary: oklch(0.97 0 0);
  --secondary-foreground: oklch(0.205 0 0);
  --muted: oklch(0.97 0 0);
  --muted-foreground: oklch(0.556 0 0);
  --accent: oklch(0.97 0 0);
  --accent-foreground: oklch(0.205 0 0);
  --destructive: oklch(0.577 0.245 27.325);
  --destructive-foreground: oklch(0.985 0 0);
  --border: oklch(0.922 0 0);
  --input: oklch(0.922 0 0);
  --ring: oklch(0.708 0 0);
  --chart-1: oklch(0.646 0.222 41.116);
  --chart-2: oklch(0.6 0.118 184.704);
  --chart-3: oklch(0.398 0.07 227.392);
  --chart-4: oklch(0.828 0.189 84.429);
  --chart-5: oklch(0.769 0.188 70.08);
  --sidebar: oklch(0.985 0 0);
  --sidebar-foreground: oklch(0.145 0 0);
  --sidebar-primary: oklch(0.205 0 0);
  --sidebar-primary-foreground: oklch(0.985 0 0);
  --sidebar-accent: oklch(0.97 0 0);
  --sidebar-accent-foreground: oklch(0.205 0 0);
  --sidebar-border: oklch(0.922 0 0);
  --sidebar-ring: oklch(0.708 0 0);
}

.dark {
  --background: oklch(0.145 0 0);
  --foreground: oklch(0.985 0 0);
  --card: oklch(0.205 0 0);
  --card-foreground: oklch(0.985 0 0);
  --popover: oklch(0.205 0 0);
  --popover-foreground: oklch(0.985 0 0);
  --primary: oklch(0.922 0 0);
  --primary-foreground: oklch(0.205 0 0);
  --secondary: oklch(0.269 0 0);
  --secondary-foreground: oklch(0.985 0 0);
  --muted: oklch(0.269 0 0);
  --muted-foreground: oklch(0.708 0 0);
  --accent: oklch(0.269 0 0);
  --accent-foreground: oklch(0.985 0 0);
  --destructive: oklch(0.704 0.191 22.216);
  --destructive-foreground: oklch(0.985 0 0);
  --border: oklch(1 0 0 / 10%);
  --input: oklch(1 0 0 / 15%);
  --ring: oklch(0.556 0 0);
  --chart-1: oklch(0.488 0.243 264.376);
  --chart-2: oklch(0.696 0.17 162.48);
  --chart-3: oklch(0.769 0.188 70.08);
  --chart-4: oklch(0.627 0.265 303.9);
  --chart-5: oklch(0.645 0.246 16.439);
  --sidebar: oklch(0.205 0 0);
  --sidebar-foreground: oklch(0.985 0 0);
  --sidebar-primary: oklch(0.488 0.243 264.376);
  --sidebar-primary-foreground: oklch(0.985 0 0);
  --sidebar-accent: oklch(0.269 0 0);
  --sidebar-accent-foreground: oklch(0.985 0 0);
  --sidebar-border: oklch(1 0 0 / 10%);
  --sidebar-ring: oklch(0.556 0 0);
}
CSS;
    }

    /**
     * Get the CSS variables for the zinc color scheme.
     */
    protected function getZincScheme(): string
    {
        return <<<'CSS'
:root {
  --radius: 0.5rem;
  --background: oklch(1 0 0);
  --foreground: oklch(0.145 0.007 286.375);
  --card: oklch(1 0 0);
  --card-foreground: oklch(0.145 0.007 286.375);
  --popover: oklch(1 0 0);
  --popover-foreground: oklch(0.145 0.007 286.375);
  --primary: oklch(0.214 0.012 286.033);
  --primary-foreground: oklch(0.985 0.002 286.601);
  --secondary: oklch(0.969 0.001 286.375);
  --secondary-foreground: oklch(0.214 0.012 286.033);
  --muted: oklch(0.969 0.001 286.375);
  --muted-foreground: oklch(0.565 0.006 286.197);
  --accent: oklch(0.969 0.001 286.375);
  --accent-foreground: oklch(0.214 0.012 286.033);
  --destructive: oklch(0.577 0.245 27.325);
  --destructive-foreground: oklch(0.985 0.002 286.601);
  --border: oklch(0.921 0.003 286.32);
  --input: oklch(0.921 0.003 286.32);
  --ring: oklch(0.708 0.006 286.279);
  --chart-1: oklch(0.646 0.222 41.116);
  --chart-2: oklch(0.6 0.118 184.704);
  --chart-3: oklch(0.398 0.07 227.392);
  --chart-4: oklch(0.828 0.189 84.429);
  --chart-5: oklch(0.769 0.188 70.08);
  --sidebar: oklch(0.985 0 0);
  --sidebar-foreground: oklch(0.141 0.005 285.823);
  --sidebar-primary: oklch(0.21 0.006 285.885);
  --sidebar-primary-foreground: oklch(0.985 0 0);
  --sidebar-accent: oklch(0.967 0.001 286.375);
  --sidebar-accent-foreground: oklch(0.21 0.006 285.885);
  --sidebar-border: oklch(0.92 0.004 286.32);
  --sidebar-ring: oklch(0.705 0.015 286.067);
}

.dark {
  --background: oklch(0.145 0.007 286.375);
  --foreground: oklch(0.985 0.002 286.601);
  --card: oklch(0.214 0.012 286.033);
  --card-foreground: oklch(0.985 0.002 286.601);
  --popover: oklch(0.214 0.012 286.033);
  --popover-foreground: oklch(0.985 0.002 286.601);
  --primary: oklch(0.921 0.003 286.32);
  --primary-foreground: oklch(0.214 0.012 286.033);
  --secondary: oklch(0.275 0.013 285.885);
  --secondary-foreground: oklch(0.985 0.002 286.601);
  --muted: oklch(0.275 0.013 285.885);
  --muted-foreground: oklch(0.708 0.006 286.279);
  --accent: oklch(0.275 0.013 285.885);
  --accent-foreground: oklch(0.985 0.002 286.601);
  --destructive: oklch(0.704 0.191 22.216);
  --destructive-foreground: oklch(0.985 0.002 286.601);
  --border: oklch(0.275 0.013 285.885);
  --input: oklch(0.275 0.013 285.885);
  --ring: oklch(0.565 0.006 286.197);
  --chart-1: oklch(0.488 0.243 264.376);
  --chart-2: oklch(0.696 0.17 162.48);
  --chart-3: oklch(0.769 0.188 70.08);
  --chart-4: oklch(0.627 0.265 303.9);
  --chart-5: oklch(0.645 0.246 16.439);
  --sidebar: oklch(0.21 0.006 285.885);
  --sidebar-foreground: oklch(0.985 0 0);
  --sidebar-primary: oklch(0.488 0.243 264.376);
  --sidebar-primary-foreground: oklch(0.985 0 0);
  --sidebar-accent: oklch(0.274 0.006 286.033);
  --sidebar-accent-foreground: oklch(0.985 0 0);
  --sidebar-border: oklch(1 0 0 / 10%);
  --sidebar-ring: oklch(0.552 0.016 285.938);
}
CSS;
    }

    /**
     * Get the CSS variables for the stone color scheme.
     */
    protected function getStoneScheme(): string
    {
        return <<<'CSS'
:root {
  --radius: 0.5rem;
  --background: oklch(0.988 0.002 106.423);
  --foreground: oklch(0.154 0.004 49.256);
  --card: oklch(0.988 0.002 106.423);
  --card-foreground: oklch(0.154 0.004 49.256);
  --popover: oklch(0.988 0.002 106.423);
  --popover-foreground: oklch(0.154 0.004 49.256);
  --primary: oklch(0.224 0.007 56.259);
  --primary-foreground: oklch(0.981 0.002 106.424);
  --secondary: oklch(0.965 0.004 60.006);
  --secondary-foreground: oklch(0.224 0.007 56.259);
  --muted: oklch(0.965 0.004 60.006);
  --muted-foreground: oklch(0.576 0.006 64.308);
  --accent: oklch(0.965 0.004 60.006);
  --accent-foreground: oklch(0.224 0.007 56.259);
  --destructive: oklch(0.577 0.245 27.325);
  --destructive-foreground: oklch(0.981 0.002 106.424);
  --border: oklch(0.921 0.005 60.648);
  --input: oklch(0.921 0.005 60.648);
  --ring: oklch(0.708 0.006 64.083);
  --chart-1: oklch(0.646 0.222 41.116);
  --chart-2: oklch(0.6 0.118 184.704);
  --chart-3: oklch(0.398 0.07 227.392);
  --chart-4: oklch(0.828 0.189 84.429);
  --chart-5: oklch(0.769 0.188 70.08);
  --sidebar: oklch(0.985 0.001 106.423);
  --sidebar-foreground: oklch(0.147 0.004 49.25);
  --sidebar-primary: oklch(0.216 0.006 56.043);
  --sidebar-primary-foreground: oklch(0.985 0.001 106.423);
  --sidebar-accent: oklch(0.97 0.001 106.424);
  --sidebar-accent-foreground: oklch(0.216 0.006 56.043);
  --sidebar-border: oklch(0.923 0.003 48.717);
  --sidebar-ring: oklch(0.709 0.01 56.259);
}

.dark {
  --background: oklch(0.154 0.004 49.256);
  --foreground: oklch(0.981 0.002 106.424);
  --card: oklch(0.224 0.007 56.259);
  --card-foreground: oklch(0.981 0.002 106.424);
  --popover: oklch(0.224 0.007 56.259);
  --popover-foreground: oklch(0.981 0.002 106.424);
  --primary: oklch(0.921 0.005 60.648);
  --primary-foreground: oklch(0.224 0.007 56.259);
  --secondary: oklch(0.281 0.006 75.849);
  --secondary-foreground: oklch(0.981 0.002 106.424);
  --muted: oklch(0.281 0.006 75.849);
  --muted-foreground: oklch(0.708 0.006 64.083);
  --accent: oklch(0.281 0.006 75.849);
  --accent-foreground: oklch(0.981 0.002 106.424);
  --destructive: oklch(0.704 0.191 22.216);
  --destructive-foreground: oklch(0.981 0.002 106.424);
  --border: oklch(0.281 0.006 75.849);
  --input: oklch(0.281 0.006 75.849);
  --ring: oklch(0.576 0.006 64.308);
  --chart-1: oklch(0.488 0.243 264.376);
  --chart-2: oklch(0.696 0.17 162.48);
  --chart-3: oklch(0.769 0.188 70.08);
  --chart-4: oklch(0.627 0.265 303.9);
  --chart-5: oklch(0.645 0.246 16.439);
  --sidebar: oklch(0.216 0.006 56.043);
  --sidebar-foreground: oklch(0.985 0.001 106.423);
  --sidebar-primary: oklch(0.488 0.243 264.376);
  --sidebar-primary-foreground: oklch(0.985 0.001 106.423);
  --sidebar-accent: oklch(0.268 0.007 34.298);
  --sidebar-accent-foreground: oklch(0.985 0.001 106.423);
  --sidebar-border: oklch(1 0 0 / 10%);
  --sidebar-ring: oklch(0.553 0.013 58.071);
}
CSS;
    }

    /**
     * Get the CSS variables for the gray color scheme.
     */
    protected function getGrayScheme(): string
    {
        return <<<'CSS'
:root {
  --radius: 0.5rem;
  --background: oklch(0.988 0.003 247.858);
  --foreground: oklch(0.145 0.028 265.118);
  --card: oklch(0.988 0.003 247.858);
  --card-foreground: oklch(0.145 0.028 265.118);
  --popover: oklch(0.988 0.003 247.858);
  --popover-foreground: oklch(0.145 0.028 265.118);
  --primary: oklch(0.221 0.037 256.743);
  --primary-foreground: oklch(0.985 0.003 247.896);
  --secondary: oklch(0.965 0.008 264.542);
  --secondary-foreground: oklch(0.221 0.037 256.743);
  --muted: oklch(0.965 0.008 264.542);
  --muted-foreground: oklch(0.573 0.021 255.377);
  --accent: oklch(0.965 0.008 264.542);
  --accent-foreground: oklch(0.221 0.037 256.743);
  --destructive: oklch(0.577 0.245 27.325);
  --destructive-foreground: oklch(0.985 0.003 247.896);
  --border: oklch(0.921 0.01 255.797);
  --input: oklch(0.921 0.01 255.797);
  --ring: oklch(0.708 0.018 254.441);
  --chart-1: oklch(0.646 0.222 41.116);
  --chart-2: oklch(0.6 0.118 184.704);
  --chart-3: oklch(0.398 0.07 227.392);
  --chart-4: oklch(0.828 0.189 84.429);
  --chart-5: oklch(0.769 0.188 70.08);
}

.dark {
  --background: oklch(0.154 0.036 264.695);
  --foreground: oklch(0.985 0.003 247.896);
  --card: oklch(0.224 0.034 256.535);
  --card-foreground: oklch(0.985 0.003 247.896);
  --popover: oklch(0.224 0.034 256.535);
  --popover-foreground: oklch(0.985 0.003 247.896);
  --primary: oklch(0.921 0.01 255.797);
  --primary-foreground: oklch(0.224 0.034 256.535);
  --secondary: oklch(0.281 0.029 257.29);
  --secondary-foreground: oklch(0.985 0.003 247.896);
  --muted: oklch(0.281 0.029 257.29);
  --muted-foreground: oklch(0.708 0.018 254.441);
  --accent: oklch(0.281 0.029 257.29);
  --accent-foreground: oklch(0.985 0.003 247.896);
  --destructive: oklch(0.704 0.191 22.216);
  --destructive-foreground: oklch(0.985 0.003 247.896);
  --border: oklch(0.281 0.029 257.29);
  --input: oklch(0.281 0.029 257.29);
  --ring: oklch(0.573 0.021 255.377);
  --chart-1: oklch(0.488 0.243 264.376);
  --chart-2: oklch(0.696 0.17 162.48);
  --chart-3: oklch(0.769 0.188 70.08);
  --chart-4: oklch(0.627 0.265 303.9);
  --chart-5: oklch(0.645 0.246 16.439);
}
CSS;
    }
}
